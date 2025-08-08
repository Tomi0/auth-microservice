import {Injectable} from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {map, Observable} from "rxjs";
import {environment} from "../../../../environments/environment";
import {AuthorizationCode} from "../../../application/shared/model/AuthorizationCode.model";

@Injectable({
  providedIn: 'root'
})
export class LoginService {

  constructor(private httpClient: HttpClient) {
  }

  public __invoke(data: any): Observable<AuthorizationCode> {
    return this.httpClient.post(environment.apiUrl + '/authorize', data).pipe(map(token => token as AuthorizationCode));
  }
}
