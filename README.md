# LanPartyFilesystem

LanPartyFilesystem is a simple web-based file storage application for a local network, for example for a LAN party to share files an games.  
It provides a lightweight PHP interface for uploading, downloading, deleting, and organizing files in folders.

The application is intentionally simple and can be run locally using XAMPP or any other Apache/PHP web server.

## Features

- Upload files through a web interface
- Create folders
- Navigate through folders
- Download files
- Delete files and folders

## Requirements

- XAMPP or another web server with PHP support
- Apache must be running
- PHP must be enabled

## Setup

### 1. Clone or download the repository

```bash
git clone https://github.com/SilasKa98/LanPartyFilesystem.git
```

Alternatively, download the repository as a ZIP file and extract it.

### 2. Move the project into the XAMPP `htdocs` folder

Move the complete project folder into:

```text
C:\xampp\htdocs\
```

The final structure should look like this:

```text
C:\xampp\htdocs\LanPartyFilesystem
├── app
├── mainStorage
└── media
```

### 3. Start Apache

Open the XAMPP Control Panel and start **Apache**.

### 4. Open the application

Open the following URL in your browser:

```text
http://localhost/LanPartyFilesystem/app/
```

or:

```text
http://127.0.0.1/LanPartyFilesystem/app/
```


## File Storage

Uploaded files are stored in the `mainStorage` folder:

```text
LanPartyFilesystem/mainStorage
```

This folder must exist and must be writable by the web server.

## Usage

1. Open the application in your browser.
2. Upload files by selecting them or using drag and drop.
3. Create folders or navigate through existing folders.
4. Use the context menu to download or delete files.

## Note

This application is intended for use inside a local network.  
Before using it in a public or production environment, additional security features should be implemented, such as:

- User authentication
- Upload limits
- File type validation
- Protection against path manipulation
- Permission and role management
