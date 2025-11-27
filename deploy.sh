#!/bin/bash

echo "🚀 Starting deployment setup..."

# Create necessary directories
mkdir -p data
mkdir -p uploads
mkdir -p includes
mkdir -p public

# Set permissions
chmod 755 data
chmod 755 uploads
chmod 755 includes

# Copy environment file if it doesn't exist
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        cp .env.example .env
        echo "📝 Created .env file from example"
    else
        echo "⚠️  No .env.example found"
    fi
fi

# Create database directory with proper permissions
if [ ! -d "data" ]; then
    mkdir data
    chmod 755 data
fi

# Create uploads directory with proper permissions  
if [ ! -d "uploads" ]; then
    mkdir uploads
    chmod 755 uploads
fi

# Check if we're on a cloud platform
if [ ! -z "$PORT" ] || [ ! -z "$RENDER" ] || [ ! -z "$KOYEB" ]; then
    echo "🌐 Detected cloud platform"
    
    # For cloud platforms using PHP built-in server
    if [ ! -f "server.php" ]; then
        echo "📁 Using PHP built-in server"
    fi
fi

# Fix any permission issues
chmod -R 755 data
chmod -R 755 uploads
chmod -R 755 includes

echo "✅ Deployment setup completed!"
