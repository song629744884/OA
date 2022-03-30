from django import forms
from django.forms.fields import CharField, IntegerField
from oa import models
from django.core.exceptions import ValidationError

class menuForm(forms.ModelForm):
    id = forms.IntegerField(required=False)
    # name = forms.CharField(max_length=10,min_length=2,label='名称')
    url = forms.CharField(label="url",required=False)
    pid = forms.IntegerField(required=False)
    icon = forms.CharField(label='图标',required=False)
    status = forms.IntegerField(required=False)
    idx = forms.IntegerField(required=False)

    class Meta:
        model = models.menu
        fields = ['name']
        exclude = []
        def __init__(self, *args, **kwargs):
            super(menuForm, self).__init__(*args, **kwargs)
            # self.fields['id'].required = False
            # self.fields['url'].required = False
            # self.fields['icon'].required = False

class userForm(forms.ModelForm):
    id = forms.IntegerField(required=False)
    #depart = forms.IntegerField(label='部门',required=False)
    pic = forms.CharField(required=False)
    # pid = forms.IntegerField()
    # icon = forms.CharField(label='图标')
    class Meta:
        model = models.user
        fields = ['name','phone','sex','birth','role_id','status','depart']
        exclude = ()
        def __init__(self, *args, **kwargs):
            super(menuForm, self).__init__(*args, **kwargs)

    def clean_phone(self):  # 局部钩子
        val = self.cleaned_data.get("phone")
        if len(val)!=11:
            raise ValidationError("手机号码长度必须为11")
        elif not val.isdigit():
            raise ValidationError("手机号码格式不正确") 
        else:
            return val

class departForm(forms.ModelForm):
    id = forms.IntegerField(required=False)
    intro = forms.CharField(required=False)

    class Meta:
        model = models.depart
        fields = ['name','pid']
        exclude = ()
        def __init__(self, *args, **kwargs):
            super(menuForm, self).__init__(*args, **kwargs)




class roleForm(forms.ModelForm):
    menus = forms.Textarea()
    class Meta:
        model = models.role
        fields = ['name']
        exclude = ()

class articleTypeForm(forms.ModelForm):
    id = forms.IntegerField(required=False)
    
    class Meta:
        model = models.articleType
        fields = ['name']
        exclude = ()
        def __init__(self, *args, **kwargs):
            super(menuForm, self).__init__(*args, **kwargs)


class articleForm(forms.ModelForm):
    id = forms.IntegerField(required=False)
    pic = forms.CharField(required=False)
    type_id = forms.IntegerField(required=False)
    user_id = forms.IntegerField(required=False)
    created_at = forms.DateTimeField(required=False)
    updated_at = forms.DateTimeField(required=False)

    

    def __init__(self, *args, **kwargs):
        super(articleForm, self).__init__(*args, **kwargs)
        self.fields['created_at'].input_formats = ['%Y-%m-%d %H:%M:%S']
        self.fields['updated_at'].input_formats = ['%Y-%m-%d %H:%M:%S']

    class Meta:
        model = models.article
        fields = ['title','content']
        exclude = ()
        def __init__(self, *args, **kwargs):
            super(menuForm, self).__init__(*args, **kwargs)