import {Injectable} from '@angular/core';
import {HttpEvent, HttpHandler, HttpInterceptor, HttpRequest} from "@angular/common/http";
import {Observable} from "rxjs";
import {Token} from "../../domain/public/login/token.model";

@Injectable({
  providedIn: 'root'
})
export class JwtInterceptorService implements HttpInterceptor {

  constructor() {
  }

  intercept(
    request: HttpRequest<any>,
    next: HttpHandler
  ): Observable<HttpEvent<any>> {

    const tokenObjetString = localStorage.getItem('token');

    const token = JSON.parse(tokenObjetString ? tokenObjetString : '{}') as Token;

    if (token.token) {
      // Clona la solicitud y agrega el encabezado de autorización con el token JWT
      request = request.clone({
        setHeaders: {
          Authorization: `Bearer ${token.token}`,
        },
      });
    }

    return next.handle(request);
  }
}
