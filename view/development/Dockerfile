FROM node:lts-alpine
ENV NODE_ENV=dev

WORKDIR /go-view

COPY package*.json ./
RUN npm rebuild node-sass
RUN npm install
COPY . .
EXPOSE 8080

CMD ["npm", "run", "serve"]