import axios from 'axios'
const api = axios.create({ baseURL: '/api/v1', headers: { 'Accept': 'application/json' } })
api.interceptors.request.use(c => { const t = localStorage.getItem('token'); if (t) c.headers.Authorization = `Bearer ${t}`; return c })
api.interceptors.response.use(r => r, e => { if (e.response?.status === 401) { localStorage.removeItem('token'); window.location.href = '/login' }; return Promise.reject(e) })

export const auth = { login: d => api.post('/auth/login', d), me: () => api.get('/auth/me') }
export const contents = {
  list: () => api.get('/contents'),
  create: d => api.post('/contents', d),
  search: q => api.get(`/contents/search?q=${q}`),
  transition: (id, t) => api.patch(`/contents/${id}/transition`, { transition: t }),
  versions: id => api.get(`/contents/${id}/versions`),
}
export const media = { list: () => api.get('/media'), upload: d => api.post('/media/upload', d) }
export default api
