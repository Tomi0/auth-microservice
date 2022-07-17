import { Injectable } from '@angular/core';
import {HttpClient, HttpParams} from "@angular/common/http";
import {map, Observable} from "rxjs";
import {environment} from "../../../../environments/environment";
import {User} from "./user.model";

@Injectable({
  providedIn: 'root'
})
export class SearchUsersService {

  constructor(private httpClient: HttpClient) { }

  public __invoke(filters: {email: string|null, admin: boolean|null}): Observable<User[]> {
    let httpParams = new HttpParams();
    if (filters.email) {
      httpParams = httpParams.set('email', filters.email);
    }
    if (filters.admin !== null) {
      httpParams = httpParams.set('admin', filters.admin);
    }

    return this.httpClient.get(environment.apiUrl + '/backoffice-admin/user/search', {params: httpParams})
      .pipe(map(jwtToken => jwtToken as User[]));
  }
}
