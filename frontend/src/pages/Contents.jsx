import { useState, useEffect } from 'react'
import { contents } from '../services/api'
import { ArrowRight, Lock } from 'lucide-react'

const statusColors = { draft: 'bg-gray-100 text-gray-700', review: 'bg-yellow-100 text-yellow-700', approved: 'bg-blue-100 text-blue-700', published: 'bg-green-100 text-green-700', archived: 'bg-red-100 text-red-700' }
const transitions = { draft: 'submit_review', review: 'approve', approved: 'publish' }

export default function Contents() {
  const [list, setList] = useState([])
  const load = () => contents.list().then(r => setList(r.data.data || r.data || [])).catch(() => {})
  useEffect(() => { load() }, [])

  const advance = async (id, status) => { const t = transitions[status]; if (t) { await contents.transition(id, t); load() } }

  return (
    <div>
      <h2 className="text-2xl font-bold mb-6">Contenidos</h2>
      <div className="space-y-3">
        {list.map(c => (
          <div key={c.id} className="bg-white rounded-xl shadow-sm p-5">
            <div className="flex items-start justify-between">
              <div className="flex-1">
                <div className="flex items-center gap-2">
                  <h3 className="font-bold">{c.title}</h3>
                  {c.locked_by && <Lock size={14} className="text-orange-500" title="Bloqueado para edición" />}
                </div>
                <p className="text-sm text-gray-500 mt-1 line-clamp-2">{c.body?.slice(0, 150)}...</p>
                <div className="flex items-center gap-3 mt-2">
                  <span className={`text-xs px-2 py-1 rounded-full ${statusColors[c.status]}`}>{c.status}</span>
                  <span className="text-xs text-gray-400">v{c.version}</span>
                  {c.slug && <span className="text-xs text-gray-400">/{c.slug}</span>}
                </div>
              </div>
              {transitions[c.status] && (
                <button onClick={() => advance(c.id, c.status)} className="flex items-center gap-1 text-xs bg-violet-50 text-violet-700 px-3 py-1.5 rounded-lg hover:bg-violet-100">
                  <ArrowRight size={12} /> {transitions[c.status]}
                </button>
              )}
            </div>
          </div>
        ))}
      </div>
    </div>
  )
}
