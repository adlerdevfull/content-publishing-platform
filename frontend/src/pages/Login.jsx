import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
import { useAuth } from '../hooks/useAuth'
export default function Login() {
  const [email, setEmail] = useState('editor@platform.test')
  const [password, setPassword] = useState('password')
  const [error, setError] = useState('')
  const { login } = useAuth()
  const navigate = useNavigate()
  const handleSubmit = async (e) => { e.preventDefault(); try { await login(email, password); navigate('/') } catch { setError('Error') } }
  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-violet-900 to-gray-900">
      <div className="bg-white rounded-xl shadow-2xl p-8 w-full max-w-md">
        <h1 className="text-2xl font-bold text-center mb-6">📝 CMS Platform</h1>
        <form onSubmit={handleSubmit} className="space-y-4">
          {error && <div className="bg-red-50 text-red-600 text-sm p-3 rounded-lg">{error}</div>}
          <input type="email" value={email} onChange={e => setEmail(e.target.value)} className="w-full px-3 py-2 border rounded-lg" />
          <input type="password" value={password} onChange={e => setPassword(e.target.value)} className="w-full px-3 py-2 border rounded-lg" />
          <button className="w-full bg-violet-600 text-white py-2 rounded-lg hover:bg-violet-700">Entrar</button>
        </form>
        <p className="text-xs text-gray-400 mt-4 text-center">editor@platform.test / password</p>
      </div>
    </div>
  )
}
