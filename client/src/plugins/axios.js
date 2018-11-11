import axios from 'axios'
import swal from 'sweetalert2'

// Request interceptor
axios.interceptors.request.use(request => {
  return request
})

// Response interceptor
axios.interceptors.response.use(
  response => response,
  error => {
    const { status } = error.response

    if (status >= 500) {
      swal({
        type: 'error',
        title: 'Server Error',
        text: 'Please contact a member of devteam',
        reverseButtons: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel'
      })
    }

    if (status === 401) {
      swal({
        type: 'warning',
        title: 'Authentication Error',
        text: 'Your session is expire please login again',
        reverseButtons: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel'
      }).then(async () => {})
    }

    if (status === 420) {
      swal({
        type: 'warning',
        title: 'Session Expired!',
        text: 'Please refresh the page and try again.',
        reverseButtons: true,
        confirmButtonText: 'OK',
        cancelButtonText: 'Cancel'
      }).then(async () => {})
    }

    return Promise.reject(error)
  }
)
