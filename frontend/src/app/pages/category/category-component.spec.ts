import { ComponentFixture, TestBed } from '@angular/core/testing';
import { NO_ERRORS_SCHEMA } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { CategoryComponent } from './category-component';

describe('CategoryComponent', () => {
  let component: CategoryComponent;
  let fixture: ComponentFixture<CategoryComponent>;

  beforeEach(() => {
    const activatedRouteStub = () => ({
      queryParams: { subscribe: (f: (arg0: {}) => any) => f({}) }
    });
    TestBed.configureTestingModule({
      schemas: [NO_ERRORS_SCHEMA],
      declarations: [CategoryComponent],
      providers: [{ provide: ActivatedRoute, useFactory: activatedRouteStub }]
    });
    fixture = TestBed.createComponent(CategoryComponent);
    component = fixture.componentInstance;
  });

  it('can load instance', () => {
    expect(component).toBeTruthy();
  });
});
