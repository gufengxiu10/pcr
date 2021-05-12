import React from "react";

import { Form, Input, Radio, RadioChangeEvent, Space, Switch } from "antd";
import MarkdownEditor from "@uiw/react-markdown-editor";
import axioas from "axios";

interface State {
  password: number;
  passwordInput: string;
}
const { TextArea } = Input;

export default class Edit extends React.Component {
  public state: State = {
    password: 0,
    passwordInput: "none",
  };

  constructor(props: {}) {
    super(props);
    this.passwordChange = this.passwordChange.bind(this);
    axioas.get("/article/6");
  }

  passwordChange(e: RadioChangeEvent) {
    this.setState({ passwordInput: e.target.value == 1 ? "block" : "none" });
  }

  render() {
    return (
      <Form labelCol={{ span: 2 }}>
        <Form.Item label="标题">
          <Input />
        </Form.Item>
        <Form.Item label="副标题">
          <TextArea rows={4} />
        </Form.Item>
        <Form.Item label="设置密码">
          <Space>
            <Radio.Group
              defaultValue={this.state.password}
              onChange={this.passwordChange}
            >
              <Radio.Button value={0}>无密码</Radio.Button>
              <Radio.Button value={1}>密码</Radio.Button>
            </Radio.Group>
            <Input style={{ display: this.state.passwordInput }} />
          </Space>
        </Form.Item>
        <Form.Item label="显示">
          <Space>
            <Form.Item>
              <Switch
                checkedChildren="开启"
                unCheckedChildren="关闭"
                defaultChecked
              />
            </Form.Item>
          </Space>
        </Form.Item>
        <Form.Item label="详情">
          <>
            <MarkdownEditor value="123165465" style={{ height: "700px" }} />
          </>
        </Form.Item>
      </Form>
    );
  }
}
