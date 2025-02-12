import Swal from "sweetalert2";

/**
 * Displays a SweetAlert notification.
 * @param {string} type - The type of alert (e.g., 'success', 'error', 'info', etc.).
 * @param {string} title - The title of the alert.
 * @param {string} message - The message to display in the alert.
 */
export const showAlert = (type, title, message) => {
  Swal.fire({
    icon: type, 
    title: title,
    text: message,
    confirmButtonText: "OK",
    customClass: {
      confirmButton: "custom-confirm-button",
    },
  });
};

export default showAlert;