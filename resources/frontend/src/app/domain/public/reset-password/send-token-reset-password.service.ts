import {Injectable} from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {map, Observable} from "rxjs";
import {environment} from "../../../../environments/environment";

@Injectable({
  providedIn: 'root'
})
export class SendTokenResetPasswordService {

  constructor(private httpClient: HttpClient) {
  }

  public __invoke(data: any): Observable<any> {
    return this.httpClient.post(environment.apiUrl + '/token-reset-password', data).pipe(map(empty => empty as any));
  }
}
