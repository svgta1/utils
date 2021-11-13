# Crypt

[TOC]



## 1 - Fonctionnement

Appel de la class : 

```php
$crypt = svgtaUtil\utils::Crypt();
```

Il est nécessaire de charger la clé RSA privée du serveur et sa clé privée de signature.
Le chargement d'une clé publique du client ou de son certificat permettra d'échanger la clé AES de façon plus sécurisée. Une clé de signature ou un certificat de signature est également préférable mais non obligatoire.

Pour générer le message à envoyer :

```php
$toSend = $crypt->setMessage($message);
```

Si la clé publique du client n'est pas fournie, le résultat sera du style 

```json
{
    'aes': clé AES de chiffrement du message,
	'crypt': {
		'cypher': message chiffré par la clé AES sous le format iv:messageChiffré,
		'sign': signature du message chiffré par la clé AES iv:sign
	},
	'type': type d'encodage base64/hex de cypher et sign - b64 ou hex
}
```

Dans le cas ou la clé publique client et fournie, le résultat sera :

```json
{
    'aes': clé AES de chiffrement du message chiffrée au format PEM,
	'crypt': {
		'cypher': message chiffré par la clé AES sous le format iv:messageChiffré,
		'sign': signature du message chiffré par la clé AES iv:sign
	},
	'type': type d'encodage base64/hex de cypher et sign - b64 ou hex
}
```

La clé AES est générée à chaque nouveau chiffrement par défaut. Un option permet de réutiliser la même clé AES.

l'IV est généré à chaque nouveau chiffrement de via AES. cypher et sign n'ont par conséquent pas le même IV.



## 2 - Paramétrages nécessaires

- Chargement de la clé privée RSA au format PEM : 

```php
$crypt->setPrivateKey($key, $password);
```

 $password est facultatif, ne sert que si la clé est protégée par un mot de passe.



- Chargement de la clé privée de signature PEM : 

```php
$crypt->setSignKey($sign, $password);
```

La clé privée de signature peut être la même que la clé RSA si son algorithme le permet.



### Génération de clés serveur

- Si pas de clé privé RSA, elle peut être générée via :

```php
$rsa = new svgta\utils\crypt\rsaCrypt();
$rsa->setKeys();
```

```php
$privateKey = $rsa->getPrivateKey(); //Clé non protégée`
```

ou

```php
$privateKey = $rsa->getProtectedKey($password); //Clé protégée par un mot de passe
```



- Si pas de clé privé de signature, elle peut être générée via :

```php
$sign = new svgta\utils\crypt\rsaSign();
$sign->setKeys();
```

```php
$signKey = $sign->getPrivateKey(); //Clé non protégée
```

ou

```php
$signKey = $sign->getProtectedKey($password); //Clé protégée par un mot de passe
```



### Récupération des clés serveur

- Clés publique :

```php
$crypt->getKeys();
```

Le format de réception est un tableau

```php
[
    "publicKey" => clé RSA publique au format PEM, 
    "publicSignKey" => clé publique de signature au format PEM
]
```

- Clés privées (utile lors de la génération par le système) :

```php
$crypt->getPrivateKeys()
```

Le format de réception est un tableau

```php
[
    "privateKey" => clé RSA publique au format PEM, 
    "privateSignKey" => clé publique de signature au format PEM
]
```

A noter que les clés privées sont fournis chiffrées si un mot de passe a été défini.

Sauvegardez vos clés et mots de passe dans un répertoire protégé.



## 3 - Paramétrages additionnels

- Encodage pour le transfert des informations. Par défaut : base64

```php
$crypt->setType($encodage); //valeurs : b64 ou hex
```

- Ajout de la clé publique de chiffrement du client au format PEM

```php
$crypt->setClientKey($key);
```

- Ajout du certificat de chiffrement du client au format PEM

```php
$crypt->setClientCert($cert);
```

- Ajout de la clé de signature du client au format PEM

```php
$crypt->setClientSignKey($key);
```

- Ajout du certificat de signature du client au format PEM

```php
$crypt->setClientSignCert($key);
```



**Fourniture de certificats**

Dans le cadre d'ajout d'un certificat, il est possible d'ajouter le CA qui a signé le CRT, au format PEM

```php
$crypt->setClientCA($pem);
```

Si le CA est fourni, il y aura vérification de la signature du certificat avec celui-ci. Sinon, la signature sera vérifiée en tant que certificat auto-signé.

Dans tous les cas, la date de validité du certificat sera vérifiée.

Si le CA est fourni, il faut appeler la méthode avant celle d'ajout du certificat client.



## 4 - Réception d'un message chiffré

- La réception doit avoir le format json suivant :

```json
{
    'aes': clé AES de chiffrement du message chiffrée au format PEM,
	'crypt': {
		'cypher': message chiffré par la clé AES sous le format iv:messageChiffré,
		'sign': signature du message chiffré par la clé AES iv:sign
	},
	'type': type d'encodage base64/hex de cypher et sign - b64 ou hex
}
```

La clé AES doit être chiffrée avec la clé publique RSA du serveur.

**sign** peut avoir la valeur *false*. Dans ce cas, la signature ne sera pas vérifiée. Dans le cas contraire, le déchiffrement se fera sur sign. Une exception sera levée en cas d'échec (non fourniture de la clé client de signature, ou mauvaise clé).

Si la signature ne correspond pas au message déchiffré du **cypher**, une exception sera levée.

Si le type d'encodage n'est pas reconnu ou ne correspond pas, un exception sera levée.



- L'appel à la méthode de récupération du message se fait via :

```php
$message = $crypt->getMessage($json);
```

