from django.db import models
from django.db.models.base import Model
from django.db.models.fields import AutoField, CharField, IntegerField

# Create your models here.
class menu(models.Model):
    id = models.AutoField(primary_key=True)
    pid = models.IntegerField()
    name = models.CharField(max_length=30)
    url = models.CharField(max_length=255)
    status = models.IntegerField()
    idx = models.IntegerField()
    icon = models.CharField(max_length=255)

    def __str__(self):
        return self.name

class user(models.Model):
    id = models.AutoField(primary_key=True)
    code = models.CharField(max_length=30)
    name = models.CharField(max_length=30)
    sex = models.IntegerField()
    birth = models.CharField(max_length=30)
    idcard = models.CharField(max_length=30)
    depart = models.CharField(max_length=30)
    phone = models.CharField(max_length=11)
    role_id = models.IntegerField()
    status = models.IntegerField()
    password = models.CharField(max_length=64)
    pic = models.CharField(max_length=255)

    def __str__(self) -> str:
        return self.name

class role(models.Model):
    id = models.AutoField(primary_key=True)
    name = models.CharField(max_length=30)
    menus = models.TextField()

    def __str__(self) -> str:
        return self.name

class depart(models.Model):
    id = models.AutoField(primary_key=True)
    name = models.CharField(max_length=30)
    pid = models.IntegerField()
    intro = models.CharField(max_length=255)

    def __str__(self) -> str:
        return self.name

class articleType(models.Model):
    id = models.AutoField(primary_key=True)
    name = models.CharField(max_length=30)

    class Meta:
        db_table = "oa_article_type"
    def __str__(self) -> str:
        return self.name

class article(models.Model):
    id = models.AutoField(primary_key=True)
    title = models.CharField(max_length=30)
    content = models.TextField()
    #type_id = models.IntegerField()
    type = models.ForeignKey('articleType', on_delete=models.CASCADE)
    pic = models.CharField(max_length=255)
    #user_id = models.IntegerField()
    user = models.ForeignKey('user', on_delete=models.CASCADE)
    created_at = models.DateTimeField()
    updated_at = models.DateTimeField()

    def __str__(self) -> str:
        return self.title 
