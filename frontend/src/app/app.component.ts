import { Component } from '@angular/core';

@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent {
  username: string | null = null;
  usernameInput: string = '';

  constructor() {}

  ngOnInit() {
    document.body.classList.toggle('dark', true);
    this.loadUsername();
  }

  private loadUsername() {
    this.username = localStorage.getItem('username');
    console.log('Wczytuję pseudonim:', this.username);
  }

  onInput(ev: any) {
    this.usernameInput = ev.target!.value;
  }

  saveUsername() {
    if (this.isValidUsername()) {
      localStorage.setItem('username', this.usernameInput);
      this.username = this.usernameInput;
    } else {
      console.log('Nieprawidłowy pseudonim!');
    }
  }

  isValidUsername(): boolean {
    return this.usernameInput.trim() !== '';
  }
}
