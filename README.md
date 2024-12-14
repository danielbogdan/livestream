# Streaming Platform Docker Composition

## Project Overview

This is a multi-container Docker application for a streaming platform, consisting of several services including a database, web application, RTMP server, and API management.

## Prerequisites

- Docker
- Docker Compose
- Git

## System Architecture

The platform includes the following key services:

1. **Database (MariaDB)**
   - Runs MariaDB 10.3
   - Stores application data
   - Persistent volume for data storage

2. **Frontend Application**
   - Apache web server
   - PHP-enabled
   - SSL configuration
   - Connects to MariaDB
   - Serves web interface

3. **RTMP Server**
   - Handles live streaming ingest
   - Supports video streaming protocols
   - Manages shared video data

4. **API Service**
   - Manages service operations
   - Provides REST-like endpoints
   - Interacts with Docker socket for service management

## Folder Structure

```
project-root/
├── frontend-app/
│   ├── mysql-data/
│   ├── app/
│   ├── certs/
│   ├── apache-conf/
│   ├── logs/
│   └── php.ini
│
└── backend-app/
    ├── facebook/
    ├── youtube/
    ├── hls/
    ├── rtmp/
    ├── shared-data/
    │   ├── livestream/
    │   ├── status/
    │   └── video/
    └── api/
```

## Folder Mapping Inside Containers

| Container      | Host Folder/Path          | Container Mount Path |
|----------------|---------------------------|----------------------|
| frontend-app   | frontend-app/app          | /var/www/html        |
| frontend-app   | frontend-app/certs        | /etc/ssl/private     |
| frontend-app   | frontend-app/apache-conf/ssl.conf | /etc/apache2/sites-available/ssl.conf |
| rtmp-server    | backend-app/shared-data   | /mnt/shared-data     |
| rtmp-server    | backend-app/video         | /mnt/livestream      |
| rtmp-server    | backend-app/errors        | /mnt/status          |
| api            | backend-app/api/api.php   | /var/www/html/api.php|

## Configuration

### Environment Variables

- `MYSQL_ROOT_PASSWORD`: Database root password
- `MYSQL_DATABASE`: Database name
- `MYSQL_USER`: Database user
- `MYSQL_PASSWORD`: Database user password
- `MYSQL_HOST`: Database host (internal Docker network)

### Ports

- **Database**: 3306
- **Web Application**: 80 (HTTP), 443 (HTTPS)
- **RTMP Server**: 1935, 8080
- **API**: 8000

## Setup Instructions

1. Clone the repository
   ```bash
   git clone <repository-url>
   cd <project-directory>
   ```

2. Create shared network
   ```bash
   docker network create shared-network
   ```

3. Build and start services
   ```bash
   docker-compose up --build -d
   ```

## API Usage

### Start a Service

```bash
curl -X POST -d "action=start_service&name=johndoe&service=hls" http://api:8000/api.php
```

![API Postman start] (./frontend-app/images/api_start.png)

![API Postman stop] (./frontend-app/images/api_stop.png)

## Security Considerations

- Use strong, unique passwords
- Rotate credentials regularly
- Limit external network access
- Use HTTPS for all web interfaces
- Implement proper network segmentation

## Troubleshooting

- Check container logs: 
  ```bash
  docker-compose logs <service-name>
  ```
- Verify network connectivity
- Ensure all volumes and configurations are correctly mounted

## Scalability

- The current setup supports horizontal scaling
- Consider implementing load balancers for high-traffic scenarios

## Maintenance

- Regularly update Docker images
- Backup database volumes
- Monitor container health and performance

## License

This project is licensed under the MIT License. Feel free to use, modify, and distribute this code as per the terms of the license.

## Contributing

Thank you for your interest in contributing to this project! As the original creator, I welcome any feedback, bug reports, or pull requests.
To contribute:

1.Fork the repository
2.Create a new branch for your feature or bug fix
3.Make your changes and test thoroughly
4.Submit a pull request detailing the changes
5.I'll review your contribution and merge it if it meets the project's standards

If you have any questions or need assistance, please don't hesitate to reach out to me.

