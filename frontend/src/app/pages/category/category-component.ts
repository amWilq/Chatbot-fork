import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'category-component',
  templateUrl: 'category-component.html',
  styleUrls: ['category-component.scss']
})
export class CategoryComponent implements OnInit {
  selectedCategory: string | null = null;
  constructor(
    private activatedRoute: ActivatedRoute
  ) { }

  ngOnInit() {
    this.activatedRoute.queryParams.subscribe((params) => {
      this.selectedCategory = params['selectedCategory'];
    });
    console.log (this.selectedCategory);
  }
}
