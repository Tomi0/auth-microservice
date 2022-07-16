import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {map, Observable} from "rxjs";
import {environment} from "../../../../environments/environment";

@Injectable({
  providedIn: 'root'
})
export class LoginService {

  constructor(private httpClient: HttpClient) { }

  public __invoke(loginData: {password: string, email: string}): Observable<string> {
    return this.httpClient.post(environment.apiUrl + '/auth/login', loginData).pipe(map(jwtToken => jwtToken as string));
  }
}
