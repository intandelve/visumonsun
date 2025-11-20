#!/usr/bin/env python3
"""
Download ERA5 10m wind (u/v) for an Indonesia bounding box using the CDS API,
coarsen/regrid and export to JSON compatible with the app's wind seeder.

Requirements:
  pip install cdsapi xarray netcdf4 numpy

Usage:
  - Configure your CDS API credentials in ~/.cdsapirc (see Copernicus CDS docs)
  - Run: python scripts/get_era5_wind.py

Output:
  - writes `public/assets/data/era5_wind.json`
"""
import json
import os
from datetime import datetime

try:
    import cdsapi
    import xarray as xr
    import numpy as np
except Exception as exc:
    print('Missing dependency:', exc)
    print('Run: pip install cdsapi xarray netcdf4 numpy')
    raise


def main():
    out_nc = 'era5_wind.nc'
    out_json = os.path.join('public', 'assets', 'data', 'era5_wind.json')

    c = cdsapi.Client()

    # Request a single time (adjust year/month/day/time as needed)
    print('Requesting ERA5 data (this may take a while)...')
    c.retrieve(
        'reanalysis-era5-single-levels',
        {
            'product_type': 'reanalysis',
            'variable': ['10m_u_component_of_wind', '10m_v_component_of_wind'],
            'year': '2025',
            'month': '11',
            'day': '09',
            'time': '01:00',
            'area': [11.0, 95.0, -11.0, 141.0],  # north, west, south, east (Indonesia bbox)
            'format': 'netcdf',
            'grid': [0.25, 0.25]
        },
        out_nc
    )

    print('Reading netCDF:', out_nc)
    ds = xr.open_dataset(out_nc)

    # variable names can be 'u10'/'v10' depending on CDS; find closest
    u_var = None
    v_var = None
    for var in ds.data_vars:
        if 'u' in var and '10' in var:
            u_var = var
        if 'v' in var and '10' in var:
            v_var = var

    if u_var is None or v_var is None:
        # fallback to common short names
        u_var = u_var or 'u10'
        v_var = v_var or 'v10'

    u = ds[u_var].squeeze()
    v = ds[v_var].squeeze()

    lats = ds['latitude'].values
    lons = ds['longitude'].values

    ny, nx = u.shape
    print('Grid size:', nx, 'x', ny)

    header = {
        'refTime': datetime.utcnow().isoformat() + 'Z',
        'nx': int(nx),
        'ny': int(ny),
        'lo1': float(lons.min()),
        'la1': float(lats.max()),
        'dx': float(lons[1] - lons[0]) if len(lons) > 1 else 0.0,
        'dy': float(abs(lats[1] - lats[0])) if len(lats) > 1 else 0.0,
        'parameterUnit': 'm.s-1'
    }

    # Flatten row-major top-to-bottom
    data = []
    for j in range(ny):
        for i in range(nx):
            lat = float(lats[j])
            lon = float(lons[i])
            data.append({
                'lat': lat,
                'lon': lon,
                'u': float(u[j, i]),
                'v': float(v[j, i])
            })

    payload = [{ 'header': header, 'data': data }]

    os.makedirs(os.path.dirname(out_json), exist_ok=True)
    with open(out_json, 'w') as f:
        json.dump(payload, f)

    print('Wrote ERA5 JSON to', out_json)


if __name__ == '__main__':
    main()
