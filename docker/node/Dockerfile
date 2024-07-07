FROM node:21.6-alpine

RUN mkdir /app

WORKDIR /app

EXPOSE 4200

RUN mkdir "/.npm"
RUN chmod -R 777 /.npm

ENTRYPOINT ["npm", "start"]
