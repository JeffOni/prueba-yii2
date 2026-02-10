# Script de prueba de la API
# Guarda este archivo como test-api.sh y ejecútalo

echo "========================================="
echo "1. Obteniendo token JWT..."
echo "========================================="

TOKEN_RESPONSE=$(curl -s -X POST http://localhost:21080/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"Admin123!"}')

echo "Respuesta:"
echo $TOKEN_RESPONSE | jq .

# Extraer token (requiere jq instalado)
TOKEN=$(echo $TOKEN_RESPONSE | jq -r '.data.token')

echo ""
echo "========================================="
echo "2. Listando productos con JWT..."
echo "========================================="

curl -s -X GET http://localhost:21080/api/v1/products \
  -H "Authorization: Bearer $TOKEN" | jq .

echo ""
echo "========================================="
echo "3. Creando un producto nuevo..."
echo "========================================="

curl -s -X POST http://localhost:21080/api/v1/products \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Producto API Test",
    "description": "Creado desde la API REST",
    "sku": "API-TEST-001",
    "price": 29.99,
    "stock": 100,
    "status": 1
  }' | jq .

echo ""
echo "========================================="
echo "Pruebas completadas!"
echo "========================================="
