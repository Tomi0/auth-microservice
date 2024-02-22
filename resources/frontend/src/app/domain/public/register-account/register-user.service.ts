import {Injectable} from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {map, Observable} from "rxjs";
import {User} from "../../../application/shared/model/User.model";
import {environment} from "../../../../environments/environment";

@Injectable({
  providedIn: 'root'
})
export class RegisterUserService {

  constructor(private httpClient: HttpClient) {
  }

  public __invoke(data: any): Observable<User> {
    return this.httpClient.post(environment.apiUrl + '/register', data).pipe(map(user => user as User));
  }
}
