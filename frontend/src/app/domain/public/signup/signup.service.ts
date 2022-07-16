import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {map, Observable} from "rxjs";
import {environment} from "../../../../environments/environment";

@Injectable({
  providedIn: 'root'
})
export class SignupService {

  constructor(private httpClient: HttpClient) { }

  public __invoke(registerData: {password: string, email: string}): Observable<any> {
    return this.httpClient.post(environment.apiUrl + '/auth/register', registerData).pipe(map(user => user as any));
  }
}
