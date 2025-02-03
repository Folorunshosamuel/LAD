import os
import requests

# Base URL for file downloads
base_url = "https://admin.placbillstrack.org/bill-uploads"

# API endpoint to fetch file data
api_url = "https://admin.placbillstrack.org/api/bills"

# Directory to save downloaded PDFs
download_dir = "bill_pdfs"
os.makedirs(download_dir, exist_ok=True)

# Function to download a file
def download_file(file_url, save_path):
    try:
        response = requests.get(file_url, stream=True)
        response.raise_for_status()
        with open(save_path, "wb") as f:
            for chunk in response.iter_content(chunk_size=1024):
                f.write(chunk)
        print(f"Downloaded: {save_path}")
    except Exception as e:
        print(f"Failed to download {file_url}: {e}")

# Fetch list of files from the API
try:
    response = requests.get(api_url)
    response.raise_for_status()
    files = response.json()  # Assuming the API returns a list of file names
    for file_name in files:  # Iterate through the list of file names
        file_url = base_url + file_name
        save_path = os.path.join(download_dir, file_name)
        download_file(file_url, save_path)
except Exception as e:
    print(f"Failed to fetch file list: {e}")
