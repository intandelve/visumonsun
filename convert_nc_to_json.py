import xarray as xr
import numpy as np
import json

print("🌬️  Converting ERA5 NetCDF to JSON for leaflet-velocity...")

# 1. Load NetCDF file
nc_file = "era5_wind.nc"
print(f"\n📂 Reading NetCDF file: {nc_file}")

try:
    ds = xr.open_dataset(nc_file)
    print("✅ File berhasil dibaca")
    print(f"   Variables: {list(ds.data_vars)}")
    print(f"   Dimensions: {dict(ds.dims)}")
except Exception as e:
    print(f"❌ Error reading file: {e}")
    exit(1)

# 2. Extract wind components
u_var = 'u10'  # Adjust if needed (u10 = 10m wind)
v_var = 'v10'  # Adjust if needed

if u_var not in ds or v_var not in ds: 
    print(f"❌ Variables {u_var} or {v_var} not found!")
    print(f"   Available variables: {list(ds.data_vars)}")
    exit(1)

# Select first time step (or adjust as needed)
u_data = ds[u_var].isel(valid_time=0).values  # Shape: (lat, lon)
v_data = ds[v_var].isel(valid_time=0).values

lats = ds['latitude'].values
lons = ds['longitude'].values

print(f"\n📊 Extracting wind components:")
print(f"   U component: {u_var}")
print(f"   V component: {v_var}")
print(f"   Lat range: [{lats.min():.2f}, {lats.max():.2f}]")
print(f"   Lon range: [{lons.min():.2f}, {lons.max():.2f}]")
print(f"   Grid shape: {u_data.shape}")

# 3. Build header (GRIB-like format for leaflet-velocity)
ny, nx = u_data.shape
lat_step = abs(lats[1] - lats[0]) if len(lats) > 1 else 1.0
lon_step = abs(lons[1] - lons[0]) if len(lons) > 1 else 1.0

header = {
    "discipline": 0,
    "disciplineName": "Meteorological products",
    "gribEdition": 2,
    "gribLength": int(nx * ny),
    "center": 98,
    "centerName": "European Centre for Medium-Range Weather Forecasts",
    "significanceOfRT": 1,
    "refTime": "2025-01-01T00:00:00Z",
    "productStatus": 0,
    "productType": 1,
    "productDefinitionTemplate": 0,
    "parameterCategory": 2,
    "parameterNumber": 2,
    "parameterNumberName":  "U-component_of_wind",
    "parameterUnit": "m. s-1",
    "genProcessType": 2,
    "forecastTime": 0,
    "surface1Type": 103,
    "surface1Value": 10.0,
    "surface2Type": 255,
    "surface2Value":  0.0,
    "gridDefinitionTemplate": 0,
    "numberPoints": int(nx * ny),
    "shape": 6,
    "gridUnits": "degrees",
    "resolution": 48,
    "winds": "true",
    "scanMode": 0,
    "nx": int(nx),
    "ny": int(ny),
    "basicAngle": 0,
    "subDivisions": 0,
    "lo1": float(lons. min()),
    "la1": float(lats.max()),  # North edge
    "lo2": float(lons.max()),
    "la2": float(lats.min()),  # South edge
    "dx":  float(lon_step),
    "dy": float(lat_step)
}

# 4. Flatten data (row-major order:  north to south, west to east)
# leaflet-velocity expects data in specific order
u_flat = u_data.flatten().tolist()
v_flat = v_data.flatten().tolist()

# Replace NaN with 0
u_flat = [0.0 if np.isnan(x) else float(x) for x in u_flat]
v_flat = [0.0 if np.isnan(x) else float(x) for x in v_flat]

# 5. Build output structure (array of 2 records:  U and V)
output = [
    {
        "header":  {
            **header,
            "parameterNumber": 2,
            "parameterNumberName": "U-component_of_wind"
        },
        "data":  u_flat
    },
    {
        "header": {
            **header,
            "parameterNumber": 3,
            "parameterNumberName": "V-component_of_wind"
        },
        "data": v_flat
    }
]

# 6. Save to JSON
output_file = "public/assets/data/era5_wind.json"
with open(output_file, 'w') as f:
    json.dump(output, f, separators=(',', ':'))  # Compact format

print(f"\n✅ JSON file created successfully!")
print(f"   Output:  {output_file}")
print(f"   Data points: {len(u_flat)}")

# 7. Statistics
u_valid = [x for x in u_flat if x != 0]
v_valid = [x for x in v_flat if x != 0]
speeds = [np.sqrt(u**2 + v**2) for u, v in zip(u_valid, v_valid)]

if speeds:
    print(f"\n📈 Wind speed statistics:")
    print(f"   Min:  {min(speeds):.2f} m/s")
    print(f"   Max: {max(speeds):.2f} m/s")
    print(f"   Mean: {np.mean(speeds):.2f} m/s")
    print(f"   Std Dev: {np.std(speeds):.2f} m/s")

print(f"\n✨ Done!  Refresh your browser to see wind animation.")