import { useState } from 'react'
import { contents } from '../services/api'
import { Search as SearchIcon } from 'lucide-react'

export default function SearchPage() {
  const [query, setQuery] = useState('')
  const [results, setResults] = useState([])
  const [searched, setSearched] = useState(false)

  const handleSearch = async (e) => {
    e.preventDefault()
    if (!query.trim()) return
    const res = await contents.search(query)
    setResults(res.data.data || res.data || [])
    setSearched(true)
  }

  return (
    <div>
      <h2 className="text-2xl font-bold mb-6">Búsqueda Full-Text (PostgreSQL GIN)</h2>
      <form onSubmit={handleSearch} className="flex gap-2 mb-6">
        <input value={query} onChange={e => setQuery(e.target.value)} placeholder="Buscar contenido... (ej: DDD, Laravel, arquitectura)" className="flex-1 px-4 py-2 border rounded-lg focus:ring-2 focus:ring-violet-500" />
        <button className="bg-violet-600 text-white px-6 py-2 rounded-lg hover:bg-violet-700 flex items-center gap-2"><SearchIcon size={16} /> Buscar</button>
      </form>
      {searched && (
        <div className="space-y-3">
          <p className="text-sm text-gray-500">{results.length} resultado(s) para "{query}"</p>
          {results.map(c => (
            <div key={c.id} className="bg-white rounded-xl shadow-sm p-5">
              <h3 className="font-bold">{c.title}</h3>
              <p className="text-sm text-gray-500 mt-1">{c.body?.slice(0, 200)}...</p>
              <div className="flex gap-2 mt-2">
                {c.keywords && (typeof c.keywords === 'string' ? JSON.parse(c.keywords) : c.keywords).map((k, i) => (
                  <span key={i} className="text-xs bg-violet-50 text-violet-700 px-2 py-0.5 rounded">{k}</span>
                ))}
              </div>
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
