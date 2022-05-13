import Swal from 'sweetalert2'

export function successAlert(config = undefined) {
  return Swal.fire({
    title: 'Perfecte!',
    text: 'Tot ha anat bé',
    icon: 'success',
    confirmButtonText: "D'acord",
    confirmButtonColor: '#374151',
    ...config,
  })
}

export function errorAlert(config = undefined) {
  return Swal.fire({
    title: 'Error!',
    text: 'Hi ha hagut un error',
    icon: 'error',
    confirmButtonText: "D'acord",
    confirmButtonColor: '#374151',
    ...config,
  })
}
