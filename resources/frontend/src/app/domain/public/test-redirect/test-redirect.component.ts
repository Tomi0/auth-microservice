import {Component} from '@angular/core';
import {ReactiveFormsModule} from "@angular/forms";
import {ActivatedRoute} from "@angular/router";

@Component({
  selector: 'app-test-redirect',
  standalone: true,
  imports: [
    ReactiveFormsModule
  ],
  templateUrl: './test-redirect.component.html',
  styleUrl: './test-redirect.component.scss'
})
export class TestRedirectComponent {

  public code: string = '';

  public constructor(protected activeRoute: ActivatedRoute) {
    this.code = this.activeRoute.snapshot.queryParamMap.get('code') || '';
  }

}
