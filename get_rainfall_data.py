import cdsapi
import xarray as xr
import mysql.connector
import os
from datetime import datetime

DB_CONFIG = {
    'host': '127.0.0.1',
    'user': 'root',
    'password': '',
    'database': 'visumonsun_db'
}
TARGET_FILE = 'rainfall_2023.nc'
MONTHS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
INDONESIA_AREA = [10, 90, -15, 150] 

print("Script started...")

conn = None
cursor = None

try:
    print(f"Contacting ERA5 (Copernicus)... (This might take a few minutes)")
    
    c = cdsapi.Client()

    c.retrieve(
        'reanalysis-era5-land-monthly-means',
        {
            'product_type': 'monthly_averaged_reanalysis',
            'variable': 'total_precipitation',
            'year': '2023',
            'month': [
                '01', '02', '03', '04', '05', '06',
                '07', '08', '09', '10', '11', '12',
            ],
            'area': INDONESIA_AREA,
            'format': 'netcdf',
        },
        TARGET_FILE)

    print(f"Data '{TARGET_FILE}' successfully downloaded.")

    print("Processing NetCDF data...")
    with xr.open_dataset(TARGET_FILE) as ds:
        monthly_avg_meters = ds['tp'].mean(dim=['latitude', 'longitude']).values
        monthly_avg_mm = monthly_avg_meters * 1000
    
    print("Data processed successfully.")

    print(f"Connecting to database '{DB_CONFIG['database']}'...")
    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor()

    print("Deleting old data (TRUNCATE rainfall_data)...")
    cursor.execute("TRUNCATE TABLE rainfall_data")

    print("Inserting 12 new ERA5 data records...")
    for i in range(12):
        query = """
        INSERT INTO rainfall_data 
        (month_name, month_index, rainfall_mm, created_at, updated_at) 
        VALUES (%s, %s, %s, %s, %s)
        """
        values = (
            MONTHS[i],
            i + 1,
            float(monthly_avg_mm[i]),
            datetime.now(),
            datetime.now()
        )
        cursor.execute(query, values)
        print(f"  -> Data {MONTHS[i]} inserted: {monthly_avg_mm[i]:.2f} mm")

    conn.commit()
    print("Database updated successfully.")

except Exception as e:
    print(f"=== AN ERROR OCCURRED ===")
    print(e)
    if conn and conn.is_connected():
        conn.rollback()
        print("Database rollback.")

finally:
    if cursor:
        cursor.close()
    if conn and conn.is_connected():
        conn.close()
        print("Database connection closed.")
        
    if os.path.exists(TARGET_FILE):
        os.remove(TARGET_FILE)
        print(f"File '{TARGET_FILE}' successfully deleted.")

print("Script finished.")