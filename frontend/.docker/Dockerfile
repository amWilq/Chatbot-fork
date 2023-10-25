# Build phase
FROM node:20-alpine as node

# Set working directory
WORKDIR /app

COPY package*.json ./

RUN npm install

COPY . .

EXPOSE 4200

CMD ["npm", "run","start"]
#RUN npm ci
#RUN npm run build
#
## Production phase
#FROM nginx:alpine
#
## Copy dependencies and built files from build phase
#COPY --from=node /app/dist/app /usr/share/nginx/html
#
## Expose port
#EXPOSE 80
#
## Start nginx
#CMD ["nginx", "-g", "daemon off;"]
