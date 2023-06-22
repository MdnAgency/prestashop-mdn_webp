# MDN WebP Support

------------------------------------

## 🇫🇷 Comment ça fonctionne ? 
Le module support WebP vous permet d'ajouter le support des formats de fichiers nouvelles générations pour Prestashop.

Le module crée une copie de vos images, dans le même dossier que vos images traditionnelles (png, jpg etc.), sans supprimer les précédentes. Ensuite, lorsqu'un utilisateur appelle n'importe quel fichier image, si son navigateur est en capacité de traiter des images en webp, le serveur retournera l'image au format webp, même si l'url appelée est sous un autre format.

__Exemple :__
produit.png et produit.webp existent sur le serveur. Votre client a un navigateur compatible webp. Il appellera le fichier produit.png, mais le serveur, lui enverra produit.webp

### Pourquoi passer par ce système  ?
- Nous n'avons pas à modifier les thèmes, ou à injecter du code
- Cela permet de rendre compatible le module avec bien d'autres, sans pour autant avoir à modifier les modules


### Installation
1. Décompresser l'archive "prestashop-mdn_webp-master.zip"
2. Renommer le répertoire obtenu en "mdn_webp"
3. Créer une archive zip "mdn_webp.zip" de ce dossier
4. Uploader sur votre Prestashop le nouveau dossier
5. Une fois installé, vous pouvez utiliser le panel WebP pour gérer vos images

 
Plus de details : [https://maisondunet.com/blog/e-commerce/module-prestashop-webp-optimisations-images/](https://maisondunet.com/blog/e-commerce/module-prestashop-webp-optimisations-images/)

------------------------------------

## 🇺🇸 How it works ? 
MDN WebP support allow you to add a support for next gen files type in Prestashop for some image type.
 
The module creates a copy of your images in the same folder as your traditional images (png, jpg, etc.), without deleting the previous ones. Then, when a user requests any image file, if their browser is capable of processing webp images, the server will return the image in webp format, even if the requested URL is in a different format.

___Example:__
Both "produit.png" and "produit.webp" exist on the server. Your client has a webp-compatible browser. They will request the "produit.png" file, but the server will send them "produit.webp".

### Why use this system?
- We don't have to modify themes or inject code.
- It allows compatibility with many other modules without having to modify them.

  
More details (in French) : [https://maisondunet.com/blog/e-commerce/module-prestashop-webp-optimisations-images/](https://maisondunet.com/blog/e-commerce/module-prestashop-webp-optimisations-images/)

------------------------------------


## How to use ?

1. Install the module
2. On the left pane, go to WebP panel
3. Generate your image

## Requirements
- Apache
  

## Contributors
[Loris Pinna](https://github.com/DevLoris)
