#!/bin/bash
# Demo: Plataforma de Gestão de Conteúdo e Publicação Dinâmica
# Uso: ./scripts/demo.sh

BASE_URL="http://localhost:8000/api"
echo "=== content-publishing-platform ==="
echo ""

echo "1. Health Check"
curl -s $BASE_URL/v1/health | jq .
echo ""

echo "2. Login (editor@platform.test / password)"
TOKEN=$(curl -s -X POST $BASE_URL/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"editor@platform.test","password":"password"}' | jq -r '.token // .access_token')
echo "   Token: ${TOKEN:0:30}..."
echo ""

echo "3. Listar Contenidos"
curl -s $BASE_URL/v1/contents \
  -H "Authorization: Bearer $TOKEN" | jq '.data'
echo ""

echo "4. Crear Contenido (Draft)"
curl -s -X POST $BASE_URL/v1/contents \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "title":"CQRS en Laravel: Guía Práctica",
    "body":"Command Query Responsibility Segregation es un patrón que separa las operaciones de lectura y escritura...",
    "category_id":2,
    "keywords":["CQRS","Laravel","patterns"],
    "visibility":"public",
    "translations":{"en":{"title":"CQRS in Laravel: Practical Guide","body":"CQRS is a pattern..."}}
  }' | jq .
echo ""

echo "5. Full-Text Search (PostgreSQL GIN)"
curl -s "$BASE_URL/v1/contents/search?q=DDD+Laravel" \
  -H "Authorization: Bearer $TOKEN" | jq '.data'
echo ""

echo "6. Transición Editorial (draft → review)"
curl -s -X PATCH $BASE_URL/v1/contents/3/transition \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"transition":"submit_review"}' | jq .
echo ""

echo "7. Ver Versiones del Contenido 1"
curl -s $BASE_URL/v1/contents/1/versions \
  -H "Authorization: Bearer $TOKEN" | jq '.data'
echo ""

echo "8. Restaurar Versión Anterior"
curl -s -X POST $BASE_URL/v1/contents/1/restore \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"version":1}' | jq .
echo ""

echo "=== Demo completada ==="
