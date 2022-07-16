import { Component, OnInit } from '@angular/core';
import {ToastrService} from "ngx-toastr";
import {Router} from "@angular/router";

@Component({
  selector: 'app-full-screen-layout',
  templateUrl: './menu-layout.component.html',
  styleUrls: ['./menu-layout.component.scss']
})
export class MenuLayoutComponent implements OnInit {

  constructor(private toastrService: ToastrService,
              private router: Router) { }

  ngOnInit(): void {
  }

  logout(): void {
    localStorage.removeItem('jwt-token');
    this.toastrService.success('Logged out successfully');
    this.router.navigate(['/auth/login']).then();
  }
}
