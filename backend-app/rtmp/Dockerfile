FROM debian:bullseye-slim

# Install required tools and dependencies
RUN apt-get update && apt-get install -y \
    wget \
    unzip \
    build-essential \
    libpcre3 \
    libpcre3-dev \
    zlib1g \
    zlib1g-dev \
    libssl-dev \
    net-tools \
    netcat-openbsd \
    iputils-ping \
    procps \
    curl \
    vim \
    ffmpeg \
    && apt-get clean

# Download and compile Nginx with RTMP module
RUN wget http://nginx.org/download/nginx-1.20.2.tar.gz && \
    wget https://github.com/arut/nginx-rtmp-module/archive/master.zip && \
    tar -zxvf nginx-1.20.2.tar.gz && \
    unzip master.zip && \
    cd nginx-1.20.2 && \
    ./configure --add-module=../nginx-rtmp-module-master --with-http_ssl_module && \
    make && make install && \
    rm -rf /var/lib/apt/lists/* nginx-1.20.2.tar.gz master.zip nginx-1.20.2 nginx-rtmp-module-master

# Copy custom nginx configuration to the correct path
COPY ./nginx.conf /usr/local/nginx/conf/nginx.conf

# Create directories for video and status
RUN mkdir -p /mnt/livestream && mkdir -p /mnt/status
VOLUME /mnt/livestream
VOLUME /mnt/status

# Expose RTMP and HTTP ports
EXPOSE 1935 8080

# Start the custom-built Nginx binary
CMD ["/usr/local/nginx/sbin/nginx", "-g", "daemon off;"]
