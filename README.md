# Excel-Importer-for-wordpress
This imports data from an excel file into wordpress posts (or pages)

This algorithm allows you to import data from excel files into blog posts or pages,
most importantly it saves images directly into the the wordpress images folder and uses a link from that folder to insert the image in the post or page

there is a specific format to the excel:

First column must be title or id and MUST BE UNIQUE
everything else is data and will be compiled one after the other 
IMAGES HAVE TO BE THE LAST COLUMN and will be placed after all the data

i've included a sample with a cute kitten image

DEPENDING ON YOUR WORDPRESS VERSION YOU MAY HAVE TO CHANGE THIS LINE IN IMPORTER.PHP

```require_once("../wp-load.php");```
for some that works, for some other it doesn't

# how to set up the script

to set it you must place all the files you see in the repostory in a folder called importer, the folder MUST be located at the location where ``wp-load.php`` is
after that, the script will look for a file called ``sample.xlsx`` (can be changed at line 10 of importer.php)

to launch the script you can either execute it from browser, which is highly unreccomended as the script WILL stop if the document is too long and it won't import all the data
or you can launch it from a ssh console which i highly reccomend

the code is commented in italian, if requested i can provide for a translation
