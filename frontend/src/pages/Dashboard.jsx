import { useState, useEffect } from 'react'
import { contents } from '../services/api'
import { FileText, Eye, Edit, Clock } from 'lucide-react'
export default function Dashboard() {
  const [list, setList] = useState([])
  useEffect(() => { contents.list().then(r => setList(r.data.data || r.data || [])).catch(() => {}) }, [])
  const byStatus = (s) => list.filter(c => c.status === s).length
  return (
    <div>
      <h2 className="text-2xl font-bold mb-6">Dashboard</h2>
      <div className="grid grid-cols-4 gap-4 mb-8">
        {[{ label: 'Borradores', value: byStatus('draft'), icon: Edit, color: 'bg-gray-500' }, { label: 'En Revisión', value: byStatus('in_review'), icon: Clock, color: 'bg-yellow-500' }, { label: 'Publicados', value: byStatus('published'), icon: Eye, color: 'bg-green-500' }, { label: 'Total', value: list.length, icon: FileText, color: 'bg-violet-500' }].map(({ label, value, icon: Icon, color }) => (
          <div key={label} className="bg-white rounded-xl shadow-sm p-5 flex items-center gap-4"><div className={`${color} p-3 rounded-lg text-white`}><Icon size={24} /></div><div><p className="text-sm text-gray-500">{label}</p><p className="text-2xl font-bold">{value}</p></div></div>
        ))}
      </div>
      <div className="bg-white rounded-xl shadow-sm p-6">
        <h3 className="font-semibold mb-3">Workflow Editorial</h3>
        <div className="flex items-center gap-2 text-sm">
          {['draft', 'in_review', 'approved', 'published', 'archived'].map((s, i) => (<span key={s} className="flex items-center gap-2"><span className="px-3 py-1 bg-gray-100 rounded-full">{s}</span>{i < 4 && <span className="text-gray-300">→</span>}</span>))}
        </div>
      </div>
    </div>
  )
}
