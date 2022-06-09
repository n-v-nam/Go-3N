module.exports = (knex) => {
  return async (query, options) => {
    const perPage = options.perPage || 10
    let page = options.page || 1

    const countQuery = knex.count('* as total').from(query.clone().as('inner'))

    if (page < 1) {
      page = 1
    }
    const offset = (page - 1) * perPage

    query.offset(offset)

    if (perPage > 0) {
      query.limit(perPage)
    }

    const [data, countRows] = await Promise.all([query, countQuery])

    const total = countRows[0].total

    return {
      pagination: {
        total: total,
        perPage,
        currentPage: page,
        lastPage: Math.ceil(total / perPage),
        from: offset,
        to: offset + data.length,
      },
      data: data,
    }
  }
}
