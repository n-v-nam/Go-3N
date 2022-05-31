FROM node:lts-alpine
ENV NODE_ENV=dev

WORKDIR /go-sub-server

COPY package*.json ./
RUN npm install
COPY . .
EXPOSE 8100

CMD ["npm", "run", "serve"]