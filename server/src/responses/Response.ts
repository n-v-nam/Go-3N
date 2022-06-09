class ResponseData<T> {
  private data: T | null;
  private message: string | null;
  status: boolean;
  constructor(status: boolean, message: string | null, data: T | null) {
    this.status = status;
    this.message = message;
    this.data = data;
  }
}

export { ResponseData };
