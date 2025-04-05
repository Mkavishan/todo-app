# Use official Node.js image as the base
FROM node:18-alpine

# Set the working directory
WORKDIR /app

# Copy package.json and package-lock.json
COPY package*.json ./

# Install frontend dependencies
RUN npm install

# Copy the rest of the frontend application
COPY . .

# Expose port 8080 to access the frontend UI
EXPOSE 8080

# Run the development server or build for production
CMD ["npm", "run", "dev"]
