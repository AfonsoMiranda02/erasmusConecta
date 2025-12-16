# VAPID
No linux ARCH/MANJARO acontece erro ao correr o seguinte comando:
```zsh
php artisan webpush:vapid
```
Ou porque o php não reconheçe o openssl ou não tem a extensão ssl
```zsh
php -m | grep openssl
```
Se o comando de cima der erro o php não está compilado com o openssl

openssl ecparam -name prime256v1 -genkey -noout -out vapid_private.pem

openssl ec -in vapid_private.pem -pubout -out vapid_public.pem

### Estas já geram com BASE64 como o laravel pede:
**Public**
```zsh
openssl ec -in vapid_private.pem -pubout -outform DER | tail -c 65 | base64 | tr '+/' '-_' | tr -d '='
```
**Private**
```zsh
openssl ec -in vapid_private.pem -pubout -outform DER | tail -c 65 | base64 | tr '+/' '-_' | tr -d '='
```