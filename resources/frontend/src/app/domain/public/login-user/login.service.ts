import { Injectable } from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {map, Observable} from "rxjs";
import {User} from "../../../application/shared/model/User.model";
import {environment} from "../../../../environments/environment";

@Injectable({
  providedIn: 'root'
})
export class LoginService {

  constructor(private httpClient: HttpClient) {
  }

  public __invoke(data: any): Observable<string> {
    return this.httpClient.post(environment.apiUrl + '/login', data).pipe(map(token => token as string));
  }
}
