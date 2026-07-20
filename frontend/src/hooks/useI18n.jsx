import { createContext, useContext, useMemo, useState, useCallback } from 'react'

const messages = {
  "en": {
    "appName": "CMS Platform",
    "appTagline": "Content & publishing",
    "logout": "Log out",
    "language": "Language",
    "login": {
      "title": "CMS Platform",
      "subtitle": "Content & publishing",
      "email": "Email",
      "password": "Password",
      "submit": "Sign in",
      "loading": "Signing in…",
      "error": "Invalid credentials"
    },
    "nav": {
      "home": "Dashboard",
      "contents": "Contents",
      "categories": "Categories"
    },
    "dashboard": "Dashboard"
  },
  "es": {
    "appName": "Plataforma CMS",
    "appTagline": "Contenido y publicación",
    "logout": "Cerrar sesión",
    "language": "Idioma",
    "login": {
      "title": "Plataforma CMS",
      "subtitle": "Contenido y publicación",
      "email": "Email",
      "password": "Contraseña",
      "submit": "Iniciar sesión",
      "loading": "Entrando…",
      "error": "Credenciales inválidas"
    },
    "nav": {
      "home": "Dashboard",
      "contents": "Contenidos",
      "categories": "Categorías"
    },
    "dashboard": "Dashboard"
  },
  "pt": {
    "appName": "Plataforma CMS",
    "appTagline": "Conteúdo e publicação",
    "logout": "Sair",
    "language": "Idioma",
    "login": {
      "title": "Plataforma CMS",
      "subtitle": "Conteúdo e publicação",
      "email": "Email",
      "password": "Senha",
      "submit": "Entrar",
      "loading": "Entrando…",
      "error": "Credenciais inválidas"
    },
    "nav": {
      "home": "Dashboard",
      "contents": "Conteúdos",
      "categories": "Categorias"
    },
    "dashboard": "Dashboard"
  }
}

const I18nContext = createContext(null)

function detect() {
  const saved = localStorage.getItem('locale')
  if (saved && messages[saved]) return saved
  const nav = (navigator.language || 'en').slice(0, 2)
  if (nav === 'es' || nav === 'pt') return nav
  return 'en'
}

export function I18nProvider({ children }) {
  const [locale, setLocaleState] = useState(detect)
  const setLocale = useCallback((next) => {
    setLocaleState(next)
    localStorage.setItem('locale', next)
    document.documentElement.lang = next
  }, [])
  const t = useCallback((key) => {
    const parts = key.split('.')
    let cur = messages[locale]
    for (const p of parts) cur = cur?.[p]
    return typeof cur === 'string' ? cur : key
  }, [locale])
  const value = useMemo(() => ({ locale, setLocale, t, messages: messages[locale] }), [locale, setLocale, t])
  return <I18nContext.Provider value={value}>{children}</I18nContext.Provider>
}

export function useI18n() {
  const ctx = useContext(I18nContext)
  if (!ctx) throw new Error('useI18n must be used within I18nProvider')
  return ctx
}
