import {knex, Knex} from "knex";
const config = require('config');

const kconfig : Knex.Config = {
  client: 'mysql',
  connection: {
    host: config?.db?.host || '127.0.0.1',
    user: config?.db?.user || 'root',
    port: config?.db?.port || 3306,
    password: config?.db?.password || '',
    database: config?.db?.database || 'zato-project',
    charset: 'utf8',
  },
  debug: false,
  migrations: {
    tableName: 'knex_migrations',
    directory: `${__dirname}/db/migrations`,
  },
  seeds: {
    directory: `${__dirname}/db/seeds`,
  },
  pool: {
    min: 2,
    max: 10,
    afterCreate: (conn: any, done: Function) => {
      done();
    },
  },
};

const knexInstance = knex(kconfig);

export default knexInstance;
