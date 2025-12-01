![Modélisation](./Documentation/Schema.png)

- To lance the project backend (PS : You need PHP CLI 8.3 minimum)
- Need Composer (Packages PHP manager) (sudo apt install composer)
- CAS utilise XM parser les réponses XML du serveur CAS (sudo apt install php8.3-xml)

```
make i
```

```
make r
```

(if the composer.json not found make this in Folder Backend/src)
```
composer init --name="sae/backend" --require="apereo/phpcas:^1.6" --no-interaction
```
