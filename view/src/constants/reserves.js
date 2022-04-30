const orderStatus = {
  waitingDriverReceive: 0,
  driverAccept: 1,
  driverRefuse: 2,
  customerCancel: 3,
  cancelAfterDriverAccept: 4,
  driverCancelAfterBothAccept: 5,
  bothAccept: 6,
  orderFail: 7,
  customerPain: 8
}
const orderStatusText = [
  'Chờ tài xế xác nhận',
  'Tài xế đã xác nhận',
  'Tài xế từ chối',
  'Nguời đặt từ chối',
  'Người đặt từ chối sau khi tài xế xác nhận',
  'Tài xế từ chối sau khi 2 bên xác nhận',
  'Cả 2 đều từ chối',
  'Đơn hàng thất bại',
  'Khách hàng đã thanh toán'
]

export { orderStatusText, orderStatus }
