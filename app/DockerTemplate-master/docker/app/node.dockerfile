# Set the base image
FROM node:20

# Set working directory
WORKDIR /var/www

# Copy `package.json` and `package-lock.json`
COPY ../../../frontend/package*.json ./

# Debugging step: list files in the working directory
RUN ls -la /var/www

# Install project dependencies
RUN npm install

# Copy project files into the docker image
COPY ../../../frontend .

# Expose the port Vite runs on
EXPOSE 3000 

# Start the Vite server
CMD ["npm", "run", "dev"]