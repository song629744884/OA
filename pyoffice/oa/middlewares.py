from django.utils.deprecation import MiddlewareMixin

from django.shortcuts import render, HttpResponse,redirect

class MD1(MiddlewareMixin):
    def process_request(self, request):
        
        if(request.path!='/oa/login/' and request.path!='/oa/views/loginIn/'):
            #print(request.path)
            if request.session.get('phone'):
                pass
            else:
                return redirect('/oa/login/')

    # def process_response(self,request, response): #基于请求响应
    #     print("md1  process_response 方法！", id(request)) #在视图之后
    #     return response

    # def process_view(self,request, view_func, view_args, view_kwargs):
    #     print("md1  process_view 方法！") #在视图之前执行 顺序执行
    #     #return view_func(request)