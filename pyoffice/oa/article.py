from django.http import request
from django.http.response import HttpResponse,JsonResponse
from django.shortcuts import redirect, render
from oa.models import articleType,user,article
from oa.form import articleTypeForm,articleForm
import json
import datetime
import time
from django.core.paginator import Paginator
from django.middleware.csrf import get_token ,rotate_token

def typeIndex(request):
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).first()
    context = {}
    context['title'] = '文档分类'
    context['me'] = me
    # request.META["CSRF_COOKIE_USED"] = True
    get_token(request)
    return render(request,'article_type.html',context)

def typeData(request):
    data = articleType.objects.all().values()
    data = list(data)
    return JsonResponse(data,safe=False)

def typeSave(request):
    if request.method == 'POST':
        params = json.loads(request.body)
        id = params.get('id')
        if id:
            obj = articleType.objects.get(pk=id)
            forms = articleTypeForm(params,instance=obj)
            if forms.is_valid():
                forms.save()
                res = {
                    'code':1,
                    'msg' : '修改成功'
                }
                return JsonResponse(res)
            else:
                error = list(forms.errors.values())[0]
                res = {
                    'code':0,
                    'msg' : error
                }
                return JsonResponse(res)
        else:
            forms = articleTypeForm(params)
            if forms.is_valid():
                data = forms.cleaned_data
                articleType.objects.create(**data)
                res = {
                    'code':1,
                    'msg' : '新增成功'
                }
                return JsonResponse(res)
            else:
                error = list(forms.errors.values())[0]
                res = {
                    'code':0,
                    'msg' : error
                }
                return JsonResponse(res)

def typeDelete(request,id):
    articleType.objects.get(pk=id).delete()
    res = {
        'code':1,
        'msg' : '删除成功' 
    }
    return JsonResponse(res)

def myArticle(request):
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).first()
    context = {}
    context['title'] = '我的文档'
    context['me'] = me
    # request.META["CSRF_COOKIE_USED"] = True
    get_token(request)
    return render(request,'myArticle.html',context)

def myData(request):
    page_num = request.GET.get('page_num')
    currentPage = request.GET.get('currentPage')
    if currentPage=='0':
        currentPage = 1
    #start = (int(currentPage)-1)*int(page_num)
    #end = start+int(page_num)
    
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).first()
    data = article.objects.filter(user_id=me.id).values('id','title','content','type_id','pic','user_id','created_at','updated_at','type__name','user__name').order_by('-created_at')
    data = list(data)
    for val in data:
        # val['created_at'] = time.strftime('%Y-%m-%d %H:%M:%S',time.strptime(val['created_at'],'%Y-%m-%dT%H:%M:%SZ'))
        #val['created_at'] = datetime.datetime.strptime(str(val['created_at']), "%Y-%m-%d %H:%M:%S"+"+00:00")
        val['created_at'] = val['created_at'].strftime("%Y-%m-%d %H:%M:%S")
    p = Paginator(data,page_num)
    page1 = p.page(currentPage)
    page1 = list(page1)
    total = article.objects.filter(user_id=me.id).count()
    res = {
        'data':page1,
        'total':total
    }
    return JsonResponse(res,safe=False)

def delete(request,id):
    article.objects.get(pk=id).delete()
    res = {
        'code':1,
        'msg' : '删除成功' 
    }
    return JsonResponse(res)

def add(request):
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).first()
    context = {}
    context['title'] = '文档添加'
    context['me'] = me
    context['id'] = ''
    # request.META["CSRF_COOKIE_USED"] = True
    get_token(request)
    return render(request,'article_form.html',context)

def edit(request):
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).first()
    context = {}
    context['title'] = '文档修改'
    context['me'] = me
    context['id'] = request.GET.get('id')
    # request.META["CSRF_COOKIE_USED"] = True
    get_token(request)
    return render(request,'article_form.html',context)

def form(request):
    id = request.GET.get('id')
    data = article.objects.filter(pk=id).values().first()
    res = {
        'code':1,
        'data' : data 
    }
    return JsonResponse(res,safe=False)

def saveForm(request):
    if request.method=='POST':
        phone = request.session.get('phone')
        me = user.objects.filter(phone=phone).first()
        params = json.loads(request.body)
        id = params.get('id')
        if id:
            obj = article.objects.get(pk=id)
            del params['created_at']
            del params['updated_at']
            forms = articleForm(params,instance=obj)
            if forms.is_valid():
                forms = forms.save(commit=False)
                #forms.user_id = me.id #修改其他数据
                forms.updated_at = datetime.datetime.now()
                forms.type_id = params.get('type_id')
                forms = forms.save()
                res = {
                    'code':1,
                    'msg' : '修改成功'
                }
                return JsonResponse(res)
            else:
                error = list(forms.errors.values())[0]
                res = {
                    'code':0,
                    'msg' : error
                }
                return JsonResponse(res)
        else:
            forms = articleForm(params)
            if forms.is_valid():
                data = forms.cleaned_data
                data['user_id'] = me.id #修改其他数据
                data['created_at'] = datetime.datetime.now()
                data['updated_at'] = datetime.datetime.now()
                article.objects.create(**data)
                res = {
                    'code':1,
                    'msg' : '新增成功'
                }
                return JsonResponse(res)
            else:
                error = list(forms.errors.values())[0]
                res = {
                    'code':0,
                    'msg' : error
                }
                return JsonResponse(res)

def index(request):
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).first()
    context = {}
    context['title'] = '文档列表'
    context['me'] = me
    # request.META["CSRF_COOKIE_USED"] = True
    get_token(request)
    return render(request,'article.html',context)

def data(request):
    page_num = request.GET.get('page_num')
    currentPage = request.GET.get('currentPage')
    if currentPage=='0':
        currentPage = 1
    
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).first()
    data = article.objects.filter().values('id','title','content','type_id','pic','user_id','created_at','updated_at','type__name','user__name').order_by('-created_at')
    data = list(data)
    for val in data:
        val['created_at'] = val['created_at'].strftime("%Y-%m-%d %H:%M:%S")
    p = Paginator(data,page_num)
    page1 = p.page(currentPage)
    page1 = list(page1)
    total = article.objects.filter().count()
    res = {
        'data':page1,
        'total':total
    }
    return JsonResponse(res,safe=False)

def view(request):
    phone = request.session.get('phone')
    me = user.objects.filter(phone=phone).first()
    context = {}
    context['title'] = '查看文档'
    context['me'] = me
    context['id'] = request.GET.get('id')
    # request.META["CSRF_COOKIE_USED"] = True
    get_token(request)
    return render(request,'article_view.html',context)