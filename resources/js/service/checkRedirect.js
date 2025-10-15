export function checkRedirect() {
  const pendingBooking = localStorage.getItem("pendingBooking");
  if (pendingBooking) {
    const { bike_id, start_date, end_date } = JSON.parse(pendingBooking);
    localStorage.removeItem("pendingBooking");
    return {
      name: "booking.step",
      query: { bike_id, start_date, end_date },
    };
  }
  return null;
}