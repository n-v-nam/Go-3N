import {knex, Knex} from "knex";

const kconfig : Knex.Config = {
  client: 'mysql',
  connection: {
    host: '127.0.0.1',
    user:  'root',
    port:  3306,
    password:  '',
    database: 'du_an_3n',
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
