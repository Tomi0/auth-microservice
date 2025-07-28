import {Component, Input} from '@angular/core';
import {FormControl, FormsModule, ReactiveFormsModule} from "@angular/forms";
import {CommonModule, NgIf} from "@angular/common";

@Component({
  selector: 'app-input',
  standalone: true,
  imports: [
    CommonModule,
    FormsModule,
    NgIf,
    ReactiveFormsModule
  ],
  templateUrl: './input.component.html',
  styleUrl: './input.component.scss'
})
export class InputComponent {

  @Input() public inputType: string = 'text';
  @Input() public label: string = '';
  @Input() public formControlInput: FormControl = {} as FormControl;
  @Input() public placeHolder: string = '';

  public identifier: string;

  public constructor() {
    this.identifier = ('' + Math.random()) + '-' + Math.random();
  }
}
