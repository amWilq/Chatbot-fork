import { Component, Input } from '@angular/core';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-tabs',
  templateUrl: 'tabs.page.html',
  styleUrls: ['tabs.page.scss']
})
export class TabsPage {
  @Input() passedVariable: boolean = false;

  constructor(private route: ActivatedRoute) {
    this.route.queryParams.subscribe(params => {
      this.passedVariable = params['questions'];
    });
  }

}
