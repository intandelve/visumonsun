#!/usr/bin/env python3
"""
Convert ERA5 NetCDF file (era5_wind.nc) to JSON format
suitable for update_wind.php script
"""

import json
import os
import sys

try:
    import xarray as xr
    import numpy as np
except ImportError:
    print("❌ Missing dependencies!")
    print("Run: pip install xarray netcdf4 numpy")
    sys.exit(1)

def convert_nc_to_json():
    # Input/Output paths
    nc_file = 'era5_wind.nc'
    json_dir = os.path.join('public', 'assets', 'data')
    json_file = os.path.join(json_dir, 'era5_wind.json')
    
    # Check if NetCDF file exists
    if not os.path.exists(nc_file):
        print(f"❌ ERROR: File '{nc_file}' tidak ditemukan!")
        sys.exit(1)
    
    print(f"📂 Reading NetCDF file: {nc_file}")
    
    try:
        # Load NetCDF file
        ds = xr.open_dataset(nc_file)
        print(f"✅ File berhasil dibaca")
        print(f"   Variables: {list(ds.data_vars)}")
        print(f"   Dimensions: {dict(ds.dims)}")
        
        # Extract u and v components
        # CDS API names them: '10m_u_component_of_wind', '10m_v_component_of_wind'
        u_var = None
        v_var = None
        
        for var_name in ds.data_vars:
            if 'u' in var_name.lower():
                u_var = var_name
            elif 'v' in var_name.lower():
                v_var = var_name
        
        if u_var is None or v_var is None:
            print(f"❌ ERROR: Tidak menemukan komponen wind u/v")
            print(f"   Available variables: {list(ds.data_vars)}")
            sys.exit(1)
        
        print(f"\n📊 Extracting wind components:")
        print(f"   U component: {u_var}")
        print(f"   V component: {v_var}")
        
        # Get the data arrays
        u_data = ds[u_var].values.flatten()
        v_data = ds[v_var].values.flatten()
        
        # Remove NaN values
        valid_mask = ~(np.isnan(u_data) | np.isnan(v_data))
        u_data = u_data[valid_mask]
        v_data = v_data[valid_mask]
        
        print(f"   Valid data points: {len(u_data)}")
        
        # Create JSON structure
        data_points = []
        for u, v in zip(u_data, v_data):
            data_points.append({
                'u': float(u),
                'v': float(v)
            })
        
        output_data = {
            'metadata': {
                'source': 'Copernicus ERA5 Reanalysis',
                'u_var': u_var,
                'v_var': v_var,
                'description': '10m wind components (u, v) from ERA5',
                'note': 'Wind speed = sqrt(u² + v²)'
            },
            'data': data_points
        }
        
        # Create directory if not exists
        os.makedirs(json_dir, exist_ok=True)
        
        # Write JSON file
        with open(json_file, 'w') as f:
            json.dump(output_data, f, indent=2)
        
        print(f"\n✅ JSON file created successfully!")
        print(f"   Output: {json_file}")
        print(f"   Data points: {len(data_points)}")
        
        # Show statistics
        speeds = np.sqrt(u_data**2 + v_data**2)
        print(f"\n📈 Wind speed statistics:")
        print(f"   Min: {np.min(speeds):.2f} m/s")
        print(f"   Max: {np.max(speeds):.2f} m/s")
        print(f"   Mean: {np.mean(speeds):.2f} m/s")
        print(f"   Std Dev: {np.std(speeds):.2f} m/s")
        
    except Exception as e:
        print(f"❌ ERROR: {str(e)}")
        sys.exit(1)

if __name__ == '__main__':
    print("🌬️  Converting ERA5 NetCDF to JSON...\n")
    convert_nc_to_json()
    print("\n✨ Done! Now run: php update_wind.php")
