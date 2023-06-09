FROM node:18.6.0-alpine

RUN npm install -g @angular/cli

RUN mkdir /app

WORKDIR /app

EXPOSE 4200

ENTRYPOINT ["npm", "start"]